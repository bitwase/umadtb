<?php
include '../conexao.php';
include '../classes.php';

//echo "<pre>";

use \Ifsnop\Mysqldump\Mysqldump;

class BackupDatabase
{
    private $backupFolder;
    private $maxNumberFiles;

    private $host;
    private $database;
    private $username;
    private $password;

    private $pdo;

    /**
     * Construtor
     *
     * @param string $backupFolder Pasta onde serão armazenados os backups
     * @param int $maxNumberFiles Número máximo de backups que serão mantido s
     */
    public function __construct($pdo, $backupFolder, $maxNumberFiles)
    {
        $this->backupFolder = $backupFolder;
        $this->maxNumberFiles = $maxNumberFiles;
        $this->pdo = $pdo;
    }

    /**
     * Define as informações de conexão com o banco de dados
     *
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function setDatabase($host, $database, $username, $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Gera um backup
     *
     * @return void
     * @throws Exception
     */
    public function generate()
    {
        // Se as informações de conexão com o banco de dados não foram definidas
        if (empty($this->database) or empty($this->username) or empty($this->host)) {
            throw new \Exception('As informações de conexão com o banco de dados não foram definidas');
        }

        // Gerando nome único para o arquivo
        $fileName = date('YmdHis') . '.sql';
        $fileNameZip = date('YmdHis');
        $filePath = $this->backupFolder . '/' . $fileName;

        // Definindo informações para geração do backup
        $dump = new Mysqldump("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password, array(
            'compress' => Mysqldump::NONE,
        ));

        // Gerando backup
        $dump->start($filePath);

        //add filePath num zip com mesmo nome, e depois remover o arquivo .sql
        //passar este novo .zip para envio
        $zip = new ZipArchive;
        $zipDir = $this->backupFolder . '/' . $fileNameZip . '.zip';
        $zip->open($zipDir, ZipArchive::CREATE);

        $zip->addFile(
            // Caminho do arquivo original
            $this->backupFolder . '/' . $fileName,
            // Novo nome do arquivo
            $fileName
        );

        $zip->close();

        $arquivo = "bkp/" . $zipDir;
        //echo "Gerado backup '{$filePath}'" . PHP_EOL;

        // Limpando backups antigos
        $this->clearOldFiles();

        $fileNameDel = $fileName;

        enviaEmail($this->pdo, $arquivo);
        deletaSql($fileNameDel);
        //echo $this->backupFolder . '/' . $fileName;
    }

    /**
     * Limpa os arquivos de backups antigos
     *
     * @return void
     */
    private function clearOldFiles()
    {
        // Buscando itens na pasta
        $files = new \DirectoryIterator($this->backupFolder);

        // Passando pelos itens
        $sortedFiles = array();
        foreach ($files as $file) {
            // Se for um arquivo
            if ($file->isFile()) {
                // Adicionando em um vetor, sendo o índice a data de modificação
                // do arquivo, para assim ordenarmos posteriormente
                $sortedFiles[$file->getMTime()] = $file->getPathName();
            }
        }

        // Ordena o vetor em ordem decrescente
        arsort($sortedFiles);

        // Passando pelos arquivos
        $numberFiles = 0;
        foreach ($sortedFiles as $file) {
            $numberFiles++;
            // Se a quantidade de arquivo for maior que a quantidade
            // máxima definida
            if ($numberFiles > $this->maxNumberFiles) {
                // Removemos o arquivo da pasta
                unlink($file);
                // echo "Apagado backup '{$file}'" . PHP_EOL;
            }
        }
    }
}


#############################################

// Incluindo o autoload do Composer para carregar a biblioteca
require_once 'vendor/autoload.php';

// Incluindo a classe que criamos
//require_once 'class/BackupDatabase.php';

// Como a geração do backup pode ser demorada, retiramos
// o limite de execução do script
set_time_limit(0);

// Utilizando a classe para gerar um backup na pasta 'backups'
// e manter os últimos dez arquivos
$con = new conecta();
//echo "<pre> $con->srv";
$backup = new BackupDatabase($pdo, 'arquivos', 30);
$backup->setDatabase($con->db_serv, $con->db, $con->db_user, $con->db_senha);
$backup->generate();

function enviaEmail($pdo, $arquivo)
{
    //print_r($pdo);
    //insere para solicitar envio 

    $cnf = new config($pdo);
    $config = $cnf->configuracoes();
    $mail_bkp = "<h3>Backup concluído.</h3>";
    $assunto = "Backup Concluído";
    $mf = $config['templateMail'];
    $mf = str_replace("##mail_title##", $assunto, $mf);
    $mf = str_replace("##mail_body##", $mail_bkp, $mf);
    $mf = addslashes($mf);


    $pdo->query("insert into tb_email (destinatario, nomeDestinatario, assunto, mensagem, st, anexo) values(
        '$config[emailBkp]',
        '$config[destinatarioBkp]',
        '$assunto',
        '$mf',
        '1',
        '$arquivo'
        )");
    $ret = $pdo->lastInsertId();
    chamaEmail($ret);
    //        echo $ret;
    //    echo "$arquivo -- Email Enviar";
}

function chamaEmail($i)
{
    $ch = curl_init();

    $postRequest = array(
        'id' => $i,
    );
    $srv = $_SERVER["HTTP_HOST"];
    $loc = $_SERVER["PHP_SELF"];
    $lc = explode("/", $loc);
    if (!isset($lc[2])) {
        $lc[2] = "";
    }
    //    echo "Srv: $srv Lc: $lc[2]";
    if ($srv == "localhost") {
        curl_setopt($ch, CURLOPT_URL, 'http://localhost/engerede/mail/index.php');
    }
    if ($srv != "localhost") {
        if ($lc[2] == "dev") {
            curl_setopt($ch, CURLOPT_URL, 'http://hom.bitwase.com/engerede/mail/index.php');
        }
        if ($lc[2] != "dev") {
            curl_setopt($ch, CURLOPT_URL, 'http://sistema.bitwase.com/mail/index.php?id=' . $i);
        }
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postRequest);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    /*    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Authorization: xxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    $headers[] = 'Content-Type: application/json';
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    */
    $result = curl_exec($ch);
    echo $result;
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}

function deletaSql($fileName)
{
    echo "$fileName";
    unlink("arquivos/" . $fileName);
}
