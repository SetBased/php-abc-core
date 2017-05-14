<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableColumn\Company\RoleTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with the details of a all role group.
 */
class RoleGroupDetailsPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the role group.
   *
   * @var int
   */
  private $rlgId;

  /**
   * The details of the role group.
   *
   * @var array
   */
  private $roleGroup;

  /**
   * The details of roles in the role group.
   *
   * @var \array
   */
  private $roles;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rlgId     = self::getCgiId('rlg', 'rlg');
    $this->roleGroup = Abc::$DL->abcSystemRoleGroupGetDetails($this->rlgId, $this->lanId);
    $this->roles     = Abc::$DL->abcSystemRoleGroupGetRoles($this->rlgId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $rlgId The ID of the role group.
   *
   * @return string
   */
  public static function getUrl($rlgId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_DETAILS, 'pag');
    $url .= self::putCgiId('rlg', $rlgId, 'rlg');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->echoDetails();

    $this->echoRoles();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the details of the role group.
   */
  private function echoDetails()
  {
    $table = new CoreDetailTable();

    // Show ID of the role group.
    NumericTableRow::addRow($table, 'ID', $this->roleGroup['rlg_id'], '%d');

    // Show name of the role group.
    TextTableRow::addRow($table, 'Name', $this->roleGroup['rlg_name']);

    // Show weight of the role group.
    TextTableRow::addRow($table, 'Weight', $this->roleGroup['rlg_weight']);

    // Show label of the role group.
    TextTableRow::addRow($table, 'Label', $this->roleGroup['rlg_label']);

    // Show table.
    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the roles in the role group.
   */
  private function echoRoles()
  {
    $table = new CoreOverviewTable();

    // Show the ID of the company.
    $table->addColumn(new NumericTableColumn(C::WRD_ID_ID, 'cmp_id'));

    // Show the name of the company.
    $table->addColumn(new TextTableColumn(C::WRD_ID_COMPANY, 'cmp_abbr'));

    // Show the name of the role.
    $table->addColumn(new RoleTableColumn(C::WRD_ID_ROLE));

    // Show the table.
    echo $table->getHtmlTable($this->roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
