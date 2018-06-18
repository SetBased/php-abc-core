<?php

namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\RoleUpdateFunctionalitiesTableAction;
use SetBased\Abc\Core\TableAction\Company\RoleUpdateTableAction;
use SetBased\Abc\Core\TableColumn\Company\FunctionalityTableColumn;
use SetBased\Abc\Core\TableColumn\Company\ModuleTableColumn;
use SetBased\Abc\Core\TableColumn\System\PageTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

/**
 * Page with information about a role.
 */
class RoleDetailsPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the role of which data is shown on this page.
   *
   * @var int
   */
  protected $rolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rolId = self::getCgiId('rol', 'rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target company.
   * @param int $rolId       The ID of the role.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId, int $rolId): string
  {
    $url = self::putCgiId('pag', C::PAG_ID_COMPANY_ROLE_DETAILS, 'pag');
    $url .= self::putCgiId('cmp', $targetCmpId, 'cmp');
    $url .= self::putCgiId('rol', $rolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showRole();

    $this->showFunctionalities();

    $this->showPages();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the functionalities that are granted to the role shown on this page.
   */
  private function showFunctionalities(): void
  {
    $functionalities = Abc::$DL->abcCompanyRoleGetFunctionalities($this->targetCmpId, $this->rolId, $this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for modifying the granted functionalities.
    $table->addTableAction('default', new RoleUpdateFunctionalitiesTableAction($this->targetCmpId, $this->rolId));

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show the ID and the name of the functionality.
    $column = new FunctionalityTableColumn('Functionality');
    $column->setSortOrder(2);
    $table->addColumn($column);

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($functionalities);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the pages that the functionality shown on this page grants access to.
   */
  private function showPages(): void
  {
    $pages = Abc::$DL->abcCompanyRoleGetPages($this->targetCmpId, $this->rolId, $this->lanId);

    $table = new CoreOverviewTable();

    // Show the ID and class of the page
    $column = new PageTableColumn('Page');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show label of the page ID.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the role.
   */
  private function showRole(): void
  {
    $details = Abc::$DL->abcCompanyRoleGetDetails($this->targetCmpId, $this->rolId, $this->lanId);

    $table = new CoreDetailTable();

    // Add action for updating the details of the role.
    $table->addTableAction('default', new RoleUpdateTableAction($this->targetCmpId, $this->rolId));

    // Show the name of the role group.
    TextTableRow::addRow($table, 'Role Group', $details['rlg_name']);

    // Show ID of the role.
    NumericTableRow::addRow($table, 'ID', $details['rol_id'], '%d');

    // Show name.
    TextTableRow::addRow($table, 'Role', $details['rol_name']);

    // Show weight.
    NumericTableRow::addRow($table, 'Weight', $details['rol_weight'], '%d');

    // Show label.
    TextTableRow::addRow($table, 'Label', $details['rol_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

