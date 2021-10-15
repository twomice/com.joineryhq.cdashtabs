<div class="cdash-inject" style="display: none;">
  <div id='crm-container' class='crm-container cdash-inject-section'>
    <table>
      <tbody>
        <tr class='crm-dashboard-{$profileName}'>
          <td>
            <div class='header-dark'>{$profileTitle}</div>
            {if $is_show_pre_post && $help_pre}
              <div class="messages help">
                {$help_pre}
              </div>
            {/if}
            <div class='view-content'>
              <div class='crm-profile-name-{$profileName}'>{$profileContent}</div>
            </div>
            {if $is_show_pre_post && $help_post}
              <div class="messages help">
                {$help_post}
              </div>
            {/if}
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
