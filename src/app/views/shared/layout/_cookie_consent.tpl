{assign settings CookieConsent::GetSettings()}

{if $settings->needsToBeConfirmed() && ($namespace!="" || $controller!="cookie_consents" || $action!="edit")}
	<div class="cookie_consent_banner_container" id="js--cookie_consent_banner_container">
		<div>
		<!--googleoff: all-->
			<div class="container-fluid">
					<h3>{!"cookie-bite"|icon}{t}Cookies{/t}</h3>
					<p>
						{t name="ATK14_APPLICATION_NAME"|dump_constant}%1 a partneři potřebují Váš souhlas k využití jednotlivých dat, aby Vám mimo jiné mohli ukazovat informace týkající se Vašich zájmů. Souhlas udělíte kliknutím na políčko „OK“.{/t}
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
