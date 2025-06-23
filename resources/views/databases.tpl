{extends file="layout/formLayout.tpl"}

{block name="title"}Bazy danych{/block}

{block name="content"}
    <databases-list :prefix="'{$prefix}'"></databases-list>
{/block}