<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
    <?php
        class Result{
            public $prices = [];

            public $nameArray = [];
            public $quantityArray = [];

            public $componentArray = [];

            public function process_csv($csvList){
                $attribute1array = [];
                $attribute2array = [];

                $rows = explode("\n", $csvList);

                foreach ($rows as $row) {
                    $attributes = explode(",", $row);

                    $attribute1 = isset($attributes[0]) ? trim($attributes[0]) : '';
                    $attribute2 = isset($attributes[1]) ? trim($attributes[1]) : '';

                    $attribute1array[] = $attribute1;
                    $attribute2array[] = $attribute2;
                }
                $this->nameArray = $attribute1array;
                $this->quantityArray = $attribute2array;
            }

            public function send_api_request(&$names, &$prices, &$categories){
                $endpoint = "https://api.mouser.com/api/v1/search/keyword?apiKey=ba482b93-5d60-43f9-b66e-49de390074f7";
                for($i = 0; $i < count($names); $i++){
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $endpoint);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
                    $headers = array(
                        "Accept: application/json",
                        "Content-Type: application/json",
                    );

                    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                    $data = <<<DATA
                    {
                        "SearchByKeywordRequest": {
                            "keyword": "{$names[$i]}",
                            "records": 1,
                            "startingRecord": 0,
                            "searchOptions": "None",
                            "searchWithYourSignUpLanguage": "false"
                        }
                    }
                    DATA;
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

                    $resp = curl_exec($curl);
                    curl_close($curl);

                    $responseData = json_decode($resp);

                    if(isset($responseData->SearchResults->Parts[0])){
                        $price = $responseData->SearchResults->Parts[0]->PriceBreaks[0]->Price;
                        $price = preg_replace('/[^0-9.]/', '', $price);
                        $priceFloat = floatval($price);

                        $priceMxn = $priceFloat * 17.63;
                        $prices[$i] = $priceMxn;
                    }else{
                        $prices[$i] = 0.0;
                    }
                    if(isset($responseData->SearchResults->Parts[0])){
                        $category = $responseData->SearchResults->Parts[0]->Category;
                        $categories[$i] = $category;
                    }
                }
            }

            public function countStringOccurrences($array) {
                $map = array();
            
                foreach ($array as $string) {
                    if (array_key_exists($string, $map)) {
                        $map[$string]++;
                    } else {
                        $map[$string] = 1;
                    }
                }
            
                return $map;
            }
            
            public function generate_table(&$array1, &$array2, &$quantityArray) {
                $maxLength = max(count($array1), count($array2));
            
                $tableContent = '<table>';
                $tableContent .= '<tr><th>Objeto</th><th>Precio</th></tr>';
            
                for ($i = 0; $i < $maxLength; $i++) {
                    $item1 = isset($array1[$i]) ? $array1[$i] : '';
                    $item2 = isset($array2[$i]) ? $array2[$i] : '';
            
                    $multiplier = isset($quantityArray[$i]) ? $quantityArray[$i] : 1;
            
                    $tableContent .= "<tr><td>". $item1 . "</td><td> $" . number_format($item2 * $multiplier, 2) . "mxn</td></tr>";
                }
            
                $tableContent .= '</table>';
                echo $tableContent;
            
                file_put_contents("resultados.txt", $tableContent);
            }
        }

        if (isset($_POST['csvData'])) {
            $csvData = $_POST['csvData'];

            $result = new Result();
            $result->process_csv($csvData);
            $result->send_api_request($result->nameArray, $result->prices, $result->componentArray);
            $result->generate_table($result->nameArray, $result->prices, $result->quantityArray);

            $categoryCount =  $result->countStringOccurrences($result->componentArray);

            
            echo "
            <div id=\"chart_div\"></div>
            <script type=\"text/javascript\">

            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages':['corechart']});
    
            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawChart);
    
            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawChart() {
    
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Topping');
            data.addColumn('number', 'Slices');
            ";

            foreach ($categoryCount as $key => $value){
                echo "
                data.addRow(['".$key."', ".$value."]);
                ";
            }

            
            echo "
                // Set chart options
                var options = {'title':'Componentes',
                                'width':400,
                                'height':300};
        
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(data, options);
                }
                </script>
                
            ";
        }
        else{
            echo "csvData not found";
        }
        
    ?>

</body>
</html>