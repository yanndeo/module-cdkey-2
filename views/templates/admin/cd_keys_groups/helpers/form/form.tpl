{extends file="helpers/form/form.tpl"}

{block name="field" append}
    {if $input['type'] == 'html'}
        {if isset($input.html_content)}
            {$input.html_content}
        {else}
            {$input.name}
        {/if}
    {/if}
{/block}