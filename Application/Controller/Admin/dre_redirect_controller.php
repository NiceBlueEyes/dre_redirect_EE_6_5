<?php

namespace Bender\dre_redirect\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;

class dre_redirect_controller extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController
{

    protected $_sClass = 'dre_redirect';

    protected $_sThisTemplate = 'dre_redirect.tpl';
    
    /**
     * stores active article for editing
     * @var oxArticle
     */
    protected $_oArticle = null;

    /**
     * @throws DatabaseErrorException
     * @throws DatabaseConnectionException
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function render()
    {
        parent::render();

        $this->addTplParam('oldLink', \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oldLink'));
        $this->addTplParam('overwrite', \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('overwrite'));

        return 'dre_redirect.tpl';
    }

    /**
     * returns active article for editing
     * @param bool $blReset
     * @return oxArticle
     */
    public function getArticle($blReset = false)
    {
        if ($this->_oArticle !== null && !$blReset) {
            return $this->_oArticle;
        }

        $soxId = $this->getEditObjectId();
        $this->_oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class); // oxNewArticle($soxId);
        $this->_oArticle->load($soxId);

        return $this->_oArticle;
    }

    /*
     * removes shop url from submitted url
     */
    private function cleanUrl($url)
    {
        return str_replace(\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sShopURL'), "", $url);
    }
    
    /**
     * Saves changes of article parameters.
     *
     * @return null
     */
    public function save()
    {
        
        $skipInsert = false;
        
        $aParams = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("editval");
        
        $sShopURL = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('sShopURL');
        
        // clean url from sShopUrl and generate "ident"
        $urlPart = $this->cleanUrl($aParams['oldLink']);
        $oxident = md5($urlPart);
        
        $overwrite = (int) $aParams['overwrite'];
        $iLang = (int) \OxidEsales\Eshop\Core\Registry::getLang()->getEditLanguage();

        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sObjectid = $soxId = $this->getEditObjectId();
        
        /*
         * check for overwrite, if overwrite is not set check if an 301 already exits.
         * else it would be possible to add the same 301 to another object in a different lang,
         * and only the first would ever do an 301, which is undefined behavior.
         */
        if ($overwrite) {
            $sDel = "DELETE FROM oxseohistory WHERE OXIDENT=?";
            $oDb->execute($sDel, [ $oxident ]);
        } else {
            //check if a ident exits
            $sCheckQuery = "SELECT COUNT(OXIDENT) from oxseohistory WHERE OXIDENT=?";
            $countResult = $oDb->execute($sCheckQuery, [ $oxident ]);
            
            if ($countResult > 0) {
                $this->addTplParam("errorCount", "ERROR: there are already " .$countResult . " entrys in seohistory table for this url, use overwrite to force addition of this redirect");
                $this->setEditObjectId($sObjectid);
                $skipInsert = true;
            }
        }
        
        if (!$skipInsert) {
            $sQ = "INSERT  INTO oxseohistory (OXOBJECTID, OXIDENT, OXSHOPID, OXLANG, OXHITS, OXINSERT, OXTIMESTAMP) VALUES ( ?, ?, 1, ? ,0, NOW() ,NOW())";
            try {
                $oDb->execute($sQ, [ $sObjectid, $oxident, $iLang ]);
                $this->addTplParam("info", "info: success") ;
            } catch (\OxidEsales\Eshop\Core\Exception\DatabaseException $e) {
                $this->addTplParam("error", "SQL failed: Redirect exists </br></br>Debug: " . $sQ .  " oxident: " . $oxident . " sObjectid " . $sObjectid . " ilang: ". $iLang ."</br></br>" . nl2br($e->getString())) ;
            }
        }
        
        $this->addTplParam('oldLink', $aParams['oldLink']);
        $result = $this->get_results($sShopURL . $aParams['oldLink']);
        $this->addTplParam("result", nl2br($result));
        $this->setEditObjectId($sObjectid);
    }
    
    public function get_results($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0"); // Necessary. The server checks for a valid User-Agent.
        curl_exec($ch);

        $response = curl_exec($ch);
        //preg_match_all('/^Location:(.*)$/mi', $response, $matches);
        curl_close($ch);

        return $response;
    }
}
