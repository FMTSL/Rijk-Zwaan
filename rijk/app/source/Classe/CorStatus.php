<?php

namespace Source\Classe;


class CorStatus
{
  public function __construct()
  {
  }
  /**
   *
   * Metodo responsavel pela definição dos dados de contrato com os CorStatus
   * @param string $companyCode
   * @param string $companyPassword
   */
  public function CorSet($cor, $id)
  {
    switch ($id) {
      case 11:
        echo ' <hr class="btn-danger"/>';
        break;
      case 5:
        $cor + ' <hr class="btn-warning"/>';
        break;
      case 7:
        $cor + ' <hr class="btn-success"/>';
        break;
      case 1:
        $cor + ' <hr class="btn-azulesuro"/>';
        break;
      case 2:
        $cor + ' <hr class="btn-azulclaro"/>';
        break;
      default:
        $cor;
        break;
    }
  }
}
