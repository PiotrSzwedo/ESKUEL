{extends file="layout/layout.tpl"}

{block name="title"}Strona główna{/block}

{block name="content"}
    <sql-editor :prefix="'{$prefix}'"></sql-editor>
{/block}