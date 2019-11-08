<?php

namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Response\SeeOtherResponse;

/**
 * Page for deleting a word.
 */
class WordDeletePage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the word.
   *
   * @var int
   */
  private $wrdId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wrdId = Abc::$cgi->getManId('wrd', 'wrd');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wrdId The ID of the word to be deleted.
   *
   * @return string
   */
  public static function getUrl(int $wrdId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_BABEL_WORD_DELETE, 'pag');
    $url .= Abc::$cgi->putId('wrd', $wrdId, 'wrd');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $details = Abc::$DL->abcBabelWordGetDetails($this->wrdId, $this->lanId);

    Abc::$DL->abcBabelWordDeleteWord($this->wrdId);

    $this->response = new SeeOtherResponse(WordGroupDetailsPage::getUrl($details['wdg_id'], $this->actLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
