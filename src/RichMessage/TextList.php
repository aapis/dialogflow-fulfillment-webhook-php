<?php

namespace Dialogflow\RichMessage;

class TextList extends \Dialogflow\RichMessage\Text
{
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
     * @param string $text containing the text response content
     *
     * @return TextList
     */
    public function text($text)
    {
        if (is_array($text)) {
            for ($i = 0; $i < sizeof($text); $i++) {
                $this->text($text[$i]);
            }
        } else if (is_string($text)) {
            $tmp = new \stdClass();
            $tmp->text = new \stdClass();
            $tmp->text->text = [$text];

            array_push($this->list, $tmp);
        }

        return $this;
    }

    /**
     * Render response as array for API V2.
     * Copied from
     * https://github.com/eristemena/dialogflow-fulfillment-webhook-php/blob/master/src/RichMessage/Text.php#L86
     *
     * @return array
     */
    protected function renderV2()
    {
        if ('google' == $this->requestSource) {
            $out = [
                'platform'        => 'ACTIONS_ON_GOOGLE',
                'simpleResponses' => [
                    'simpleResponses' => [],
                ],
            ];

            for ($i = 0; $i < sizeof($this->list); $i++) {
                $list = $this->list[$i];

                if ($this->ssml) {
                    $out['simpleResponses']['simpleResponses'][0]['ssml'] = $this->ssml;
                } else {
                    $out['simpleResponses']['simpleResponses'][0]['textToSpeech'] = $list->text->text[0];
                }

                $out['simpleResponses']['simpleResponses'][0]['displayText'] = $list->text->text[0];
            }

            return $out;
        } else {
            $out = $this->list;

            if (in_array($this->requestSource, $this->supportedRichMessagePlatforms)) {
                $out['platform'] = $this->v2PlatformMap[$this->requestSource];
            }

            return $out;
        }
    }
}