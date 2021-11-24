<div class="cdashtabs-inject" style="display: none;">
  <div id="crm-container" class="crm-container cdashtabs-inject-section">
    <table>
      <tbody>
        <tr class="crm-dashboard-{$profileName}">
          <td>
            <div class="header-dark"><a class="cdashtabs-section-anchor" id="section-{$profileName}"></a>{$profileTitle}</div>
            {if $is_show_pre_post && $help_pre}
              <div class="messages help">
                {$help_pre}
              </div>
            {/if}
            <div class="view-content">
              <div class="crm-profile-name-{$profileName}">{$profileContent}</div>
            </div>
            {if $is_show_pre_post && $help_post}
              <div class="messages help">
                {$help_post}
              </div>
            {/if}
            {if $is_edit}
              <div class="cdashtabs-profile-buttons crm-submit-buttons">
                {crmButton p="civicrm/profile/edit" q="reset=1&gid=$ufId&id=$userContactId&cdashtabsdest=$cdashtabsdest" icon="pencil"}{ts}Edit{/ts}{/crmButton}
              </div>
            {/if}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
