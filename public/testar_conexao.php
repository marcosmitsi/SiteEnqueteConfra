<?php
require_once '../model/DB.php';

$conn = DB::getConnection();
echo "✅ Conexão estabelecida com sucesso!";
