<?php

namespace SetBased\Abc\Core\TableColumn;

/**
 * Abstract table column with icon linking to page with details or information of an entity.
 */
abstract class DetailsIconTableColumn extends IconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->iconUrl  = ICON_SMALL_DETAILS;
    $this->altValue = 'details';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
