<div class="card bg-light text-dark">
	<div class="card-body">
		<h5 class="card-title">О себе</h5>
		<div class="row">
			<div class="col">
				<div class="form-group required">
					<label for="tb0101_idn">ИИН</label>
					<input type="text" class="form-control" data-mask="000 000 000 000" id="tb0101_idn" name="tb0101_idn" value="<?=@$tb0101["tb0101_idn"]?>" required>
				</div>
			</div>
			<div class="col">
				<div class="form-group required">
					<label for="tb0101_name1">Фамилия</label>
					<input type="text" class="form-control" id="tb0101_name1" name="tb0101_name1" value="<?=@$tb0101["tb0101_name1"]?>" required>
				</div>
			</div>
			<div class="col">
				<div class="form-group required">
					<label for="tb0101_name2">Имя</label>
					<input type="text" class="form-control" id="tb0101_name2" name="tb0101_name2" value="<?=@$tb0101["tb0101_name2"]?>" required>
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label for="tb0101_name3">Отчество</label>
					<input type="text" class="form-control" id="tb0101_name3" name="tb0101_name3" value="<?=@$tb0101["tb0101_name3"]?>">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="form-group <?=$this->uri->segment(2)=="registration"?" required":""?>">
					<label for="tb0101_passwd1">Пароль</label>
					<input type="password" class="form-control" id="tb0101_passwd1" name="tb0101_passwd1" <?=$this->uri->segment(2)=="registration"?" required":""?>>
				</div>
			</div>
			<div class="col">
				<div class="form-group <?=$this->uri->segment(2)=="registration"?" required":""?>">
					<label for="tb0101_passwd2">Пароль (повтор)</label>
					<input type="password" class="form-control" id="tb0101_passwd2" name="tb0101_passwd2" <?=$this->uri->segment(2)=="registration"?" required":""?>>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col">
				<div class="form-group required">
					<label for="tb0101_email">Email</label>
					<input type="email" class="form-control" id="tb0101_email" name="tb0101_email" value="<?=@$tb0101["tb0101_email"]?>" required>
				</div>
			</div>
			<div class="col">
				<div class="form-group required">
					<label for="tb0101_phone1">Телефон 1</label>
					<input type="tel" class="form-control" id="tb0101_phone1" name="tb0101_phone1" value="<?=@$tb0101["tb0101_phone1"]?>" required>
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label for="tb0101_phone2">Телефон 2</label>
					<input type="tel" class="form-control" id="tb0101_phone2" name="tb0101_phone2" value="<?=@$tb0101["tb0101_phone2"]?>">
				</div>
			</div>
		</div>
	</div>
</div>