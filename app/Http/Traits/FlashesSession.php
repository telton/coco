<?php

namespace App\Http\Traits;

use Laracasts\Flash\FlashNotifier;

trait FlashesSession
{
    /**
     * Flash a message.
     *
     * @author Tyler Eltonn <telton@umflint.edu>
     * @param        $message
     * @param string $level
     *
     * @return FlashNotifier
     */
    public function flash($message = null, $level = 'info')
    {
        $notifier = app(FlashNotifier::class);

        if (is_null($message)) {
            return $notifier;
        }

        return $notifier->message($message, $level);
    }
}
