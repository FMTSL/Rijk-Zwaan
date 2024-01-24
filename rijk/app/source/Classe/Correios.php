<?php

namespace Source\Classe;


class Correios
{
  const URL_BASE = 'http://ws.correios.com.br';


  /**
   * Codigo de serviço dos correios
   *  @var string
   */
  const SERVICE_SEDEX       = "04162";
  const SERVICE_SEDEX_10    = "04790";
  const SERVICE_SEDEX_12    = "04782";
  const SERVICE_SEDEX_HOJE  = "04804";
  const SERVICE_PAC         = "04669";

  /**
   * Codigo dos formatos
   * @var integer
   */
  const FORMAT_CAIXA_PACOTE = 1;
  const FORMAT_ROLO_PRISMA  = 2;
  const FORMAT_ENVELOPE     = 3;

  private $companyCode;
  private $companyPassword;

  /**
   *
   * Metodo responsavel pela definição dos dados de contrato com os Correios
   * @param string $companyCode
   * @param string $companyPassword
   * 
   * ///$companyCode = 12208884
   * ///$companyPassword = 13256985
   */
  public function __construct($companyCode = null, $companyPassword = null)
  {
    $this->companyCode      = $companyCode;
    $this->companyPassword  = $companyPassword;
  }


  /**
   * Metodo responsavel por calcular o frete dos nos Correios
   * @param string $serviceCode
   * @param string $zipcodeOrigin
   * @param string $zipcodeDestiny
   * @param float $weight
   * @param integer $format
   * @param integer $length
   * @param integer $height
   * @param integer $width
   * @param integer $diameter
   * @param boolean $ownHand
   * @param integer $declaredValue
   * @param boolean $acknowledgmentReceipt
   * @return object
   */
  public function calculateShipping($serviceCode, $zipcodeOrigin, $zipcodeDestiny, $weight, $format, $length, $height, $width, $diameter = 0, $ownHand = false, $declaredValue = 0, $acknowledgmentReceipt = false)
  {
    $params = [
      'nCdEmpresa'          => $this->companyCode,
      'sDsSenha'            => $this->companyPassword,
      'nCdServico'          => $serviceCode,
      'sCepOrigem'          => $zipcodeOrigin,
      'sCepDestino'         => $zipcodeDestiny,
      'nVlPeso'             => $weight,
      'nCdFormato'          => $format,
      'nVlComprimento'      => $length,
      'nVlAltura'           => $height,
      'nVlLargura'          => $width,
      'nVlDiametro'         => $diameter,
      'sCdMaoPropria'       => $ownHand ? 'S' : 'N',
      'nVlValorDeclarado'   => $declaredValue,
      'sCdAvisoRecebimento' => $acknowledgmentReceipt ? 'S' : 'N',
      'StrRetorno' => 'xml'
    ];

    $query = http_build_query($params);

    $result = $this->get('/calculador/CalcPrecoPrazo.aspx?' . $query);
    //print_r($result);

    return $result ? $result->cServico : null;
    //return $result ? $result : null;
  }

  /**
   * Metodo responsavel por executar a consulta no Webservice dos correios
   * @param string $resource
   * @return object
   */
  public function get($resource)
  {
    $endPoint = self::URL_BASE . $resource;

    $curl = curl_init();
    curl_setopt_array($curl, [
      CURLOPT_URL => $endPoint,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => 'GET'
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    return strlen($response) ? simplexml_load_string($response) : null;
  }
}