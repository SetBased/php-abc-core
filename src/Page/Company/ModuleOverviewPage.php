<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\ModuleUpdateTableAction;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
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
  public static function getUrl($targetCmpId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_MODULE_OVERVIEW, 'pag');
    $url .= self::putCgiVar('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $modules = Abc::$DL->companyModuleGetAllEnabled($this->targetCmpId, $this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for modifying the enabled modules of the target company.
    $table->addTableAction('default', new ModuleUpdateTableAction($this->targetCmpId));

    // Show the ID of the module.
    $table->addColumn(new NumericTableColumn('ID', 'mdl_id'));

    // Show the name of the module.
    $col = $table->addColumn(new TextTableColumn('Model', 'mdl_name'));
    $col->setSortOrder(1);

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
