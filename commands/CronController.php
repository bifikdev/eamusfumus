<?php

namespace app\commands;

use KebaCorp\VaultSecret\VaultSecret;
use app\models\TelegramUsers;
use TelegramBot\Api\BotApi;
use yii\console\Controller;
use app\forms\FormRequest;
use yii\console\ExitCode;

class CronController extends Controller
{

    /**
     * Cron Telegram Message Update
     */
    public function actionTelegram()
    {
        $bot = new BotApi(VaultSecret::getSecret('TELEGRAM_TOKEN'));
        foreach (TelegramUsers::find()->all() as $index => $user) {
            try {
                $bot->editMessageText($user->id, $user->idMessage, $this->getTemplate($user));
            } catch (\Exception $e) {
                $e->getMessage();
                //echo $e->getMessage() . PHP_EOL;
            }

        }
        return ExitCode::OK;
    }

    /**
     * @param TelegramUsers $model
     * @return string
     */
    protected function getTemplate(TelegramUsers $model): string
    {
        return self::getMessage('MESSAGE_TEMPLATE', [
            'username' => $model->username,
            'active' => ($model->isActive) ? self::UNICODE_ON : self::UNICODE_OFF,
            'ready' => ($model->isReady) ? self::UNICODE_OFFICE : self::UNICODE_HOME,
            'smokeRequestAll' => FormRequest::getCountAll(),
            'smokeRequestWithMe' => FormRequest::getCount($model->id),
            'lastSmokeRequest' => FormRequest::getLast(),

            'iconSmoke' => self::UNICODE_SMOKE,
            'iconTimer' => self::UNICODE_TIMER,
            'iconHome' => self::UNICODE_HOME,
            'iconOffice' => self::UNICODE_OFFICE
        ]);
    }

    /**
     * @param string $message
     * @param array $context
     * @return string
     */
    protected static function getMessage(string $message, array $context = []): string
    {
        return \Yii::t('telegram', $message, $context);
    }

    const UNICODE_ON = "\u{2705}";
    const UNICODE_OFF = "\u{274E}";

    const UNICODE_TIMER = "\u{23F3}";
    const UNICODE_SMOKE = "\u{1F6AC}";

    const UNICODE_HOME = "\u{1F3E0}";
    const UNICODE_OFFICE = "\u{1F3E2}";

    const UNICODE_YES = "\u{2705}";
    const UNICODE_NO = "\u{274E}";
    const UNICODE_WAIT = "\u{23F3}";
}