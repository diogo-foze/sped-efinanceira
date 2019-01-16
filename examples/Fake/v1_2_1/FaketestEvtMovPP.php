<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../../bootstrap.php';

use NFePHP\eFinanc\Event;
use NFePHP\Common\Certificate;

$config = [
    'tpAmb' => 2, //tipo de ambiente 1 - Produção; 2 - pre-produção
    'verAplic' => '43_0', //Versão do processo de emissão do evento. Informar a versão do aplicativo emissor do evento.
    'eventoVersion' => '1_2_1', //versão do layout do evento
    'cnpjDeclarante' => '99999999999999'
];
$configJson = json_encode($config, JSON_PRETTY_PRINT);

$std = new \stdClass();
$std->sequencial = '1';
$std->indretificacao = 3;
$std->nrrecibo = '123456789012345678-12-123-1234-123456789012345678';
$std->tpni = 2;
$std->tpdeclarado = 'klsks';
$std->nideclarado = 'ssss';
$std->nomedeclarado = 'slkcskkslsklsklsk';
$std->anomescaixa = '201712';

$std->infoprevpriv[0] = new \stdClass();
$std->infoprevpriv[0]->numproposta = '12';
$std->infoprevpriv[0]->numprocesso = '22222';
$std->infoprevpriv[0]->tpproduto = '01';
$std->infoprevpriv[0]->tpplano = '02';
$std->infoprevpriv[0]->vlrprincipal = 10111.11;
$std->infoprevpriv[0]->vlrrendimentos = 1111.11;

$std->infoprevpriv[0]->aplic[0] = new \stdClass();
$std->infoprevpriv[0]->aplic[0]->vlrcontribuicao = 1111.11;
$std->infoprevpriv[0]->aplic[0]->vlrcarregamento = 10000.00;
$std->infoprevpriv[0]->aplic[0]->vlrpartpf = 5000.00;
$std->infoprevpriv[0]->aplic[0]->vlrpartpj = 6000.00;
$std->infoprevpriv[0]->aplic[0]->cnpj = '12345678901234';

$std->infoprevpriv[0]->resg[0] = new \stdClass();
$std->infoprevpriv[0]->resg[0]->vlraliquotairrf = 10.11;
$std->infoprevpriv[0]->resg[0]->numanoscarencia = 8.15;
$std->infoprevpriv[0]->resg[0]->vlrresgateprincipal = 11111.11;
$std->infoprevpriv[0]->resg[0]->vlrresgaterendimentos = 1.11;
$std->infoprevpriv[0]->resg[0]->vlrirrf = 14.54;

$std->infoprevpriv[0]->benef[0] = new \stdClass();
$std->infoprevpriv[0]->benef[0]->tpni = 2;
$std->infoprevpriv[0]->benef[0]->niparticipante = '45343434';
$std->infoprevpriv[0]->benef[0]->codreceita = '3277';
$std->infoprevpriv[0]->benef[0]->prazovigencia = 874;
$std->infoprevpriv[0]->benef[0]->vlrmensalinicial = 2451.56;
$std->infoprevpriv[0]->benef[0]->vlrbruto = 2875.54;
$std->infoprevpriv[0]->benef[0]->vlrliquido = 1865.22;
$std->infoprevpriv[0]->benef[0]->vlrirrf = 110.11;
$std->infoprevpriv[0]->benef[0]->vlraliquotairrf = 12.01;
$std->infoprevpriv[0]->benef[0]->competenciapgto = '11';

$std->infoprevpriv[0]->saldofinal= new \stdClass();
$std->infoprevpriv[0]->saldofinal->vlrprincipal = 11457.59;
$std->infoprevpriv[0]->saldofinal->vlrrendimentos = 2598.89;

try {
    
   //carrega a classe responsavel por lidar com os certificados
    $content     = file_get_contents('expired_certificate.pfx');
    $password    = 'associacao';
    $certificate = Certificate::readPfx($content, $password);
    
    //cria o evento e retorna o XML assinado
    $xml = Event::evtMovPP(
        $configJson,
        $std,
        $certificate,
        '2017-08-03 10:37:00'
    )->toXml();
    
    //$xml = Event::f3000($json, $std, $certificate)->toXML();
    //$json = Event::evtMovOpFin($configjson, $std, $certificate)->toJson();
    
    header('Content-type: text/xml; charset=UTF-8');
    echo $xml;
    
} catch (\Exception $e) {
    echo $e->getMessage();
}
