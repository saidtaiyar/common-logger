# common-logger
logger for app of php7

The logger can to send your logged message to email and notice to Telegram.
<pre>
'max_file_size' - max size of file log
'files_root' - root directory (example:'/var/log/php-app-zvsv-logger/')
'mail_to' - a email which can used for notifier,
-- smtp
'smtp_username' - email 'from',
'smtp_port' => '465',
'smtp_host' - for example yandex's smtp ('ssl://smtp.yandex.ru')
'smtp_password'
'smtp_charset' - for example 'utf-8',
'smtp_from' => for example 'Santa Claus'
-- telegram
'telegram_url' - some url from Telegram (watch to Telegram api),
'telegram_chat_id',
</pre>

File with parameters must be in the constructor when your doing the first call the class Logger.

Example:
<pre>
\zvsv\commonLogger\Logger::getInstance('params-logger.php')
    ->write('file-log', ['some data']);
</pre>

If want to send the data to your email, you must set the third parameter "true"

Example:
<pre>
\zvsv\commonLogger\Logger::getInstance()->write('file-log', ['some data'], true);
</pre>