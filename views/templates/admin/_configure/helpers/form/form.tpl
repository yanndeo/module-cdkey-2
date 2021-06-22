{extends file="helpers/form/form.tpl"}
{block name="field" append}
    {if $input.type == 'switch'}
        {foreach $input.values as $value}
            <label {if $value.value == 1} for="{$input.name}_on"{else} for="{$input.name}_off"{/if}>
                {if $value.value == 1}
                    {l s='Yes' mod='cdkeys'}
                {else}
                    {l s='No'  mod='cdkeys'}
                {/if}
            </label>
            <div class="margin-form">
                <input type="radio" name="{$input.name}"{if $value.value == 1} id="{$input.name}_on"{else} id="{$input.name}_off"{/if} value="{$value.value}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
            </div>
        {/foreach}
    {/if}
{/block}