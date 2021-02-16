<?php

namespace Dialogflow\RichMessage;

class TelephonyTransfer extends RichMessage
{
    /**
     * Phone number to transfer to
     *
     * @var string
     */
    protected $number = '';

    /**
     * SSML to be read prior to transferring the call, if desired
     *
     * @var string
     */
    protected $ssml = '';

    /**
     * All the text items we want to send out to Dialogflow
     *
     * @var array
     */
    protected $list = [];

    /**
     * Create a new Text instance.
     *
     * @return TextList
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Set the text for a Text.
     *
     * @param string $number Phone number to redirect to
     *
     * @return TextList
     */
    public function toPhone(string $number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Set the SSML for a Text.
     *
     * @param string $text containing the SSML response content
     */
    public function ssml($ssml)
    {
        $this->ssml = $ssml;

        return $this;
    }

    /**
     * Render response as array for API V2.
     *
     * @return array
     */
    protected function renderV2()
    {
        if ('voice' == $this->requestSource) {
            $out = [
                [
                    'platform' => 'TELEPHONY',
                    'telephonyTransferCall' => [
                        'phoneNumber' => $this->number
                    ]
                ]
            ];

            if ($this->ssml) {
                $out[] = [
                    'platform' => 'TELEPHONY',
                    'telephonySynthesizeSpeech' => [
                        'ssml' => $this->ssml
                    ]
                ];
            }

            return $out;
        }
    }
}
