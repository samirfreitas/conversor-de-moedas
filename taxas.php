
<?php
$servername = "localhost";
$username = "id2877829_convert";
$password = "123@mudar";
$dbname = "id2877829_conversordemoedas";

// Criando conexão
$conn = new mysqli($servername, $username, $password,$dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//consultando taxas das moedas no banco de dados
$sql = "SELECT jsonTaxas, dataAtualizacao FROM taxaMoedas";
$result = $conn->query($sql);

// verificando se algum resultado foi encontrado
if ($result->num_rows > 0) {
    
        while($row = $result->fetch_assoc()) {
            if($row["dataAtualizacao"] < date("Y-m-d H:i:s",strtotime('-30 minutes'))){

                // consultando api das taxas
                $json =file_get_contents('https://openexchangerates.org/api/latest.json?app_id=fd73872f21184dd9b888597a236f6eda');
                //montando query para atualização das taxas no banco
                $sql = "UPDATE taxaMoedas SET jsonTaxas='$json',dataAtualizacao='" . date("Y-m-d H:i:s") . "' WHERE id=1";
                
                //executando a query para atualização do banco de dados
               if($conn->query($sql) === TRUE) {
                    echo json_decode($json);
                }else{
                    echo "erro";
                }

            }else{

                echo $row["jsonTaxas"];

            }
        }
}else{
echo "nenhuma resultado encontrado";
}
?>
