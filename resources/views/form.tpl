{extends file="layout/formLayout.tpl"}

{block name="title"}Dodawanie bazy danych{/block}

{block name="content"}
    <database-add-form :prefix="'{$prefix}'"></database-add-form>
{/block}