{extends file="layout/layout.tpl"}

{block name="title"}SQL{/block}

{block name="content"}
    <sql-editor :prefix="'{$prefix}'"></sql-editor>
{/block}