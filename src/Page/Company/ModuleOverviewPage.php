<?php

namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\ModuleUpdateTableAction;
use SetBased\Abc\Core\TableColumn\Company\ModuleTableColumn;

/**
 * Page with an overview of the enabled modules of a company.
 */
class ModuleOverviewPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_COMPANY_MODULE_OVERVIEW, 'pag');
    $url .= Abc::$cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $modules = Abc::$DL->abcCompanyModuleGetAllEnabled($this->targetCmpId, $this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for modifying the enabled modules of the target company.
    $table->addTableAction('default', new ModuleUpdateTableAction($this->targetCmpId));

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
