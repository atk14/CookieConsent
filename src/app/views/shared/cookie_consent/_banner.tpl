{assign settings CookieConsent::GetSettings()}

{if $settings->needsToBeConfirmed() && ($namespace!="" || $controller!="cookie_consents" || $action!="edit")}
	<div class="cookie_consent_banner_container" id="js--cookie_consent_banner_container">
		<div>
		<!--googleoff: all-->
			<div class="container-fluid">
					<h3>{!"cookie-bite"|icon}{t}Cookies{/t}</h3>
					<p>
						{t app_name="ATK14_APPLICATION_NAME"|dump_constant escape=false}K provozování webu %1 využíváme takzvané cookies. Cookies jsou soubory sloužící k přizpůsobení obsahu webu, k měření jeho funkčnosti a k zajištění vaší maximální spokojenosti. Souhlas s používáním cookies udělíte kliknutím na tlačítko „OK“.{/t}
						{* <a href="{"privacy_policy"|link_to_page}">{t}Více informací{/t}</a> *}
					</p>
					
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
