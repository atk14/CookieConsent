{assign consent CookieConsent::GetInstance()}
{assign settings CookieConsent::GetSettings()}

{if $settings->needsToBeConfirmed() && ($namespace!="" || $controller!="cookie_consents" || $action!="edit")}
	<div class="cookie_consent_banner_container" id="js--cookie_consent_banner_container">
		<div>
		<!--googleoff: all-->
			<div class="container-fluid">
					<h3>{!"cookie-bite"|icon} {$consent->getBannerTitle()}</h3>

					{!$consent->getBannerText()|markdown}
					
					<p>
						{a_remote namespace="" controller="cookie_consents" action="edit" _class="btn btn-secondary"}{t}Nastavení{/t}{/a_remote}
						{a_remote namespace="" controller="cookie_consents" action="accept_all" _class="btn btn-primary" _method=post}{t}Ok{/t}{/a_remote}
					</p>

					<p class="rejection-link">
						{capture assign=a}<a href="{link_to namespace="" controller="cookie_consents" action="reject_all"}" class="remote_link post" data-remote="true" data-method="post">{/capture}
						{t 1=$a escape=false}Souhlas můžete odmítnout %1 zde </a>{/t}
					</p>
			</div>
		<!--googleon: all-->
		</div>
	</div>
{/if}
