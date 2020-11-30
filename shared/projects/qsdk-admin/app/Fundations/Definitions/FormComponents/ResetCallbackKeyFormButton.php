<?php
namespace App\Fundations\Definitions\FormComponents;

use App\Models\GameApp;
use Encore\Admin\Form\Field;

class ResetCallbackKeyFormButton extends Field
{
    protected $view = 'admin.components.form.reset-callback-key';

    public function render()
    {
        $callbackKeyGenerator = function (): string {
            return GameApp::generateNextCallbackKey();
        };

        $callBackKeys = [];
        for ($i = 0; $i < 100; $i++) {
            $callBackKeys[] = $callbackKeyGenerator();
        }
        $callBackKeysToString = json_encode($callBackKeys);

        $this->script = <<<SCRIPT
        var callbackKey = {$callBackKeysToString}
        $('#reset-callback-key-btn-{$this->id}').click(function (e){
           $('#reset-callback-key-input-{$this->id}').val(callbackKey.pop())
           e.preventDefault();
        });
SCRIPT;

        return parent::render();
    }
}
