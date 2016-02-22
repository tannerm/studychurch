<fieldset class="rcp_card_fieldset">
	<label>Credit Card Information</label>
	<p id="rcp_card_number_wrap">
		<input type="text" size="20" maxlength="20" name="rcp_card_number" class="rcp_card_number card-number" placeholder="Card Number"/>
	</p>

	<p id="rcp_card_name_wrap">
		<input type="text" size="20" name="rcp_card_name" class="rcp_card_name card-name" placeholder="Name on Card" />
	</p>
	<div class="row">
		<p id="rcp_card_exp_wrap" class=" small-5 columns">
			<input type="text" name="rcp_card_exp_month" class="rcp_card_exp_month card-expiry-month" maxlength="2" placeholder="MM" style="width: 3rem; display:inline-block;" />
			<span class="rcp_expiry_separator"> / </span>
			<input type="text" maxlength="4" name="rcp_card_exp_year" class="rcp_card_exp_year card-expiry-year" placeholder="YYYY" style="width: 4rem; display:inline-block;" />
		</p>
		<p id="rcp_card_cvc_wrap" class="small-3 columns">
			<input type="text" size="4" maxlength="4" name="rcp_card_cvc" class="rcp_card_cvc card-cvc" placeholder="CVC"/>
		</p>

		<p id="rcp_card_zip_wrap" class="small-4 columns">
			<input type="text" size="4" name="rcp_card_zip" class="rcp_card_zip card-zip" placeholder="Zip Code" />
		</p>
	</div>

</fieldset>
