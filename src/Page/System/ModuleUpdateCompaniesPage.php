<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\SlatControlFactory\SystemModuleUpdateCompaniesSlatControlFactory;
use Plaisio\Core\Page\TabPage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Form\LouverForm;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page for granting or revoking a module to or from companies.
 */
class ModuleUpdateCompaniesPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the module.
   *
   * @var array
   */
  private array $details;

  /**
   * The form shown on this page.
   *
   * @var LouverForm
   */
  private LouverForm $form;

  /**
   * The ID of the module that will be granted or revoked to or from companies.
   *
   * @var int
   */
  private int $modId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->modId = Nub::$nub->cgi->getManId('mdl', 'mdl');

    $this->details = Nub::$nub->DL->abcSystemModuleGetDetails($this->modId, $this->lanId);

    Nub::$nub->assets->appendPageTitle($this->details['mdl_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $modId The ID of the module.
   *
   * @return string
   */
  public static function getUrl(int $modId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_UPDATE_COMPANIES, 'pag');
    $url .= Nub::$nub->cgi->putId('mdl', $modId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showModule();

    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $pages = Nub::$nub->DL->abcSystemModuleGetAvailableCompanies($this->modId);

    $this->form = new LouverForm();
    $this->form->setFactory(new SystemModuleUpdateCompaniesSlatControlFactory())
               ->setData($pages)
               ->addSubmitButton(C::WRD_ID_BUTTON_UPDATE, 'handleForm')
               ->populate();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit, i.e. add or removes pages to the functionality.
   */
  private function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    foreach ($changes['data'] as $cmp_id => $dummy)
    {
      if ($values['data'][$cmp_id]['mdl_granted'])
      {
        Nub::$nub->DL->abcCompanyModuleEnable($cmp_id, $this->modId);
      }
      else
      {
        Nub::$nub->DL->abcCompanyModuleDisable($cmp_id, $this->modId);
      }
    }

    // Use brute force to proper profiles.
    Nub::$nub->DL->abcProfileProper();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm(): void
  {
    $method = $this->form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->form->defaultHandler($method);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm(): void
  {
    $this->databaseAction();

    $this->response = new SeeOtherResponse(ModuleDetailsPage::getUrl($this->modId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the functionality.
   */
  private function showModule(): void
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the module.
    IntegerTableRow::addRow($table, 'ID', $this->details['mdl_id']);

    // Add row for the module name.
    TextTableRow::addRow($table, 'Module', $this->details['mdl_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
