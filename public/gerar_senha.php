<?php
$senha = '243156'; // 🔑 Mude aqui se quiser outra senha
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo "Senha criptografada: <br><code>$hash</code>";
