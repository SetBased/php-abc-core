<?php

namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

/**
 * Page for inserting a word.
 */
class WordInsertPage extends WordBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wdgId       = Abc::$cgi->getManId('wdg', 'wdg');
    $this->buttonWrdId = C::WRD_ID_BUTTON_INSERT;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wdgId The ID of the word group.
   *
   * @return string
   */
  public static function getUrl(int $wdgId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_BABEL_WORD_INSERT, 'pag');
    $url .= Abc::$cgi->putId('wdg', $wdgId, 'wdg');
    $url .= Abc::$cgi->putId('act_lan', C::LAN_ID_BABEL_REFERENCE, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a word.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();

    $this->wrdId = Abc::$DL->abcBabelWordInsertWord($this->wdgId,
                                               $values['wrd_label'],
                                               $values['wrd_comment'],
                                               $values['wdt_text']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function setValues(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

