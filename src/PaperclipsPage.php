<?php

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

class PaperClipsPage
{
    /**
     * @var RemoteWebDriver
     */
    private $webdriver;
    public function __construct()
    {
        $this->webdriver = RemoteWebDriver::create('http://' . CONFIG_SELENIUM_HOST .'/wd/hub', DesiredCapabilities::chrome());
    }

    public function openPaperClips()
    {
        $this->webdriver->get(CONFIG_PAPERCLIP_URL);
    }

    public function isAutoClipperAvailable()
    {
        try {
            $this->webdriver->findElement(WebDriverBy::cssSelector('#btnMakeClipper:not([disabled])'));
            return true;
        } catch (NoSuchElementException $e) {
            return false;
        }
    }

    public function buyAutoClipper()
    {
        $this->webdriver->findElement(WebDriverBy::cssSelector('#btnMakeClipper:not([disabled])'))->click();
    }

    public function makePaperClip()
    {
        $this->webdriver->findElement(WebDriverBy::id('btnMakePaperclip'))->click();
    }

    public function isWireEmpty()
    {
        return $this->webdriver->findElement(WebDriverBy::id('wire'))->getText() === '0' ? true : false;
    }

    public function buyWire()
    {
        $this->webdriver->findElement(WebDriverBy::id('btnBuyWire'))->click();
    }

    public function raisePrice()
    {
        $this->webdriver->findElement(WebDriverBy::id('btnRaisePrice'))->click();
    }

    public function lowerPrice()
    {
        $this->webdriver->findElement(WebDriverBy::id('btnLowerPrice'))->click();
    }

    public function isProjectAvailable()
    {
        try {
            $this->webdriver->findElement(WebDriverBy::cssSelector('.projectButton:not([disabled])'));
            return true;
        } catch (NoSuchElementException $e) {
            return false;
        }
    }

    public function buyProject()
    {
        $this->webdriver->findElement(WebDriverBy::cssSelector('.projectButton:not([disabled])'))->click();
    }

    public function isTrustAvailable()
    {
        try {
            $this->webdriver->findElement(WebDriverBy::cssSelector('#btnAddProc:not([disabled])'));
            return true;
        } catch (NoSuchElementException $e) {
            return false;
        }
    }

    public function buyProcessor()
    {
        $this->webdriver->findElement(WebDriverBy::cssSelector('#btnAddProc'))->click();
    }

    public function buyMemory()
    {
        $this->webdriver->findElement(WebDriverBy::cssSelector('#btnAddMem'))->click();
    }

    public function getAvgClipsSoldPerSec()
    {
        return $this->webdriver->findElement(WebDriverBy::cssSelector('#avgSales'))->getAttribute('innerHTML');
    }

    public function getAvgClipsMadePerSec()
    {
        return $this->webdriver->findElement(WebDriverBy::cssSelector('#clipmakerRate'))->getAttribute('innerHTML');
    }

    public function isMarketingAvailable()
    {
        try {
            $this->webdriver->findElement(WebDriverBy::cssSelector('#btnExpandMarketing:not([disabled])'));
            return true;
        } catch (NoSuchElementException $e) {
            return false;
        }
    }

    public function buyMarketing()
    {
        $this->webdriver->findElement(WebDriverBy::cssSelector('#btnExpandMarketing'))->click();
    }

    public function getNumberOfAutoclippers()
    {
        return $this->webdriver->findElement(WebDriverBy::id('clipmakerLevel2'))->getAttribute('innerHTML');
    }

    public function getAutoClipperCost()
    {
        return $this->webdriver->findElement(WebDriverBy::id('clipperCost'))->getAttribute('innerHTML');
    }

    public function getFunds()
    {
        return $this->webdriver->findElement(WebDriverBy::id('funds'))->getAttribute('innerHTML');
    }
}