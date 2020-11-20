{if $action eq 2}
  {include file="CRM/Cdashtabs/Form/Section.tpl"}
{else}
<div class="crm-content-block crm-block">
  <table id="options" class="row-highlight">
   <thead>
   <tr>
      <th>{ts}Label{/ts}</th>
      <th>{ts}Type{/ts}</th>
      <th>{ts}ID{/ts}</th>
      <th>{ts}Order{/ts}</th>
      <th></th>
    </tr>
    </thead>
    <tbody>
  {foreach from=$rows item=row}
    <tr id="option_value-{$row.id}" class="crm-admin-options crm-admin-options_{$row.id} crm-entity {cycle values="odd-row,even-row"}{if NOT $row.is_active} disabled{/if}">
      <td class="no-wrap crm-admin-options-label">
        {$row.label}
      </td>
      <td class="nowrap crm-admin-options-type">{$row.type}</td>
      <td class="nowrap crm-admin-options-order">{$row.sectionId}</td>
      <td class="nowrap crm-admin-options-order">{$row.weight}</td>
      <td>{$row.action|replace:'xx':$row.id}</td>
    </tr>
  {/foreach}
  </tbody>
  </table>
  <div class="action-link">
    {crmButton p="civicrm/admin/cdashtabs/settings" q="reset=1"}{ts}Settings{/ts}{/crmButton}
  </div>
</div>
{/if}
