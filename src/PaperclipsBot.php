<?php

class PaperclipsBot
{
    /**
     * @var int interval in micro seconds (1 second = 1.000.000 ms) to tedious boring work, f.e. "Make Paperclip"
     */
    private $highFrequencyHeartbeat = 100000;

    /**
     * @var int interval in micro seconds (1 second = 1.000.000 ms) to important work, f.e. buying stuff
     */
    private $lowFrequencyHeartbeat = 5000000;

    private $playing = false;

    /**
     * @var string This is the order what to buy with trust. 'M' stands for 'Memory', 'P' stands for 'Processor'.
     */
    private $behaviour = [
        'trustStrategy' => 'MPMMPMMMPMMPMPM',
        'autoclipper_max_price' => 100,
        'keep_x_dollars_in_your_pocket_for_wire' => 30,
        'ignore_pocket_up_to_x_autoclippers' => 5,
        'stop_making_manual_paperclips_when_having_x_autoclippers' => 5
    ];

    /**
     * @var int
     */
    private $autoclippers;

    /**
     * @var PaperClipsPage page object to interact with web browser
     */
    private $page;

    public function __construct()
    {
        $this->page = new PaperClipsPage();
        $this->autoclippers = 0;
    }

    public function play()
    {
        $this->playing = true;

        $this->page->openPaperclips();

        $heartbeatInterval = 0;

        while ($this->playing) {
            $this->doHighFrequencyStuff();

            usleep($this->highFrequencyHeartbeat);
            $heartbeatInterval += $this->highFrequencyHeartbeat;

            if ($heartbeatInterval >= $this->lowFrequencyHeartbeat) {
                $this->doLowFrequencyStuff();
                $heartbeatInterval = 0;
            }
        }

    }

    private function doHighFrequencyStuff()
    {
        if ($this->autoclippers < $this->behaviour['stop_making_manual_paperclips_when_having_x_autoclippers']) {
            $this->page->makePaperClip();
        }
    }

    private function doLowFrequencyStuff()
    {
        if ($this->page->isMarketingAvailable()) {
            $this->page->buyMarketing();
        }

        if ($this->page->isAutoClipperAvailable()
            && (
                $this->autoclippers < $this->behaviour['ignore_pocket_up_to_x_autoclippers']
                || ((int) $this->page->getFunds() > (int) $this->behaviour['keep_x_dollars_in_your_pocket_for_wire'])
            )) {
            $this->page->buyAutoClipper();
            $this->autoclippers++;
        }

        if ($this->page->isWireEmpty()) {
            $this->page->buyWire();
        }

        if ($this->page->isProjectAvailable()) {
            $this->page->buyProject();
        }

        if ($this->page->isTrustAvailable()) {
            $this->buyProcessorOrMemory();
        }

        $this->optimizePrice();
    }

    private function buyProcessorOrMemory()
    {
        if (!isset($this->behaviour['trustStrategy'][0])) {
            // out of strategy .. dont know what to do
            return;
        }
        if ('M' === $this->behaviour['trustStrategy'][0]) {
            $this->page->buyMemory();
        } elseif ('P' === $this->behaviour['trustStrategy'][0]) {
            $this->page->buyProcessor();
        } else {
            throw new RuntimeException(
                'Invalid letter "' . $this->behaviour['trustStrategy'][0] . '" in trust strategy"'
            );
        }

        // Remove first letter from strategy to proceed in strategy
        $this->behaviour['trustStrategy'] = substr($this->behaviour['trustStrategy'], 1);
    }

    private function optimizePrice()
    {
        if ($this->page->getAvgClipsMadePerSec() > $this->page->getAvgClipsSoldPerSec()) {
            $this->page->lowerPrice();
        } else {
            $this->page->raisePrice();
        }
    }
}