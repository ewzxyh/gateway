<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nome_fantasia',
        'razao_social',
        'cartao_cnpj',
        'username',
        'email',
        'password',
        "cpf_cnpj",
        "cpf",
        "data_nascimento",
        "telefone",
        "saldo",
        "total_transacoes",
        "permission",
        "avatar",
        "status",
        "data_cadastro",
        "ip_user",
        "transacoes_aproved",
        "transacoes_recused",
        "valor_sacado",
        "valor_saque_pendente",
        "taxa_cash_in",
        "taxa_cash_out",
        "taxa_cash_in_fixa",
        "taxa_cash_out_fixa",
        "token",
        "banido",
        "cliente_id",
        "taxa_percentual",
        "volume_transacional",
        "valor_pago_taxa",
        "user_id",
        "cep",
        "rua",
        "estado",
        "cidade",
        "bairro",
        "numero_residencia",
        "complemento",
        "foto_rg_frente",
        "foto_rg_verso",
        "selfie_rg",
        "media_faturamento",
        "indicador_ref",
        "whitelisted_ip",
        "pushcut_pixpago",
        "twofa_secret",
        "code_ref",
        "indicador_ref",
        "gerente_id",
        "gerente_percentage",
        "gerente_aprovar",
        "webhook_url",
        "webhook_endpoint",
        "integracao_utmfy"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gerente_aprovar' => 'boolean',
            "webhook_endpoint" => 'array'
        ];
    }

    public function chaves()
    {
        return $this->belongsTo(UsersKey::class, 'username', 'user_id');
    }

    // Relação com o usuário indicado
    public function indicador()
    {
        return $this->belongsTo(User::class, 'indicador_ref', 'code_ref');
    }

    // Relação com os usuários que foram indicados
    public function clientes()
    {
        return $this->hasMany(User::class, 'indicador_ref', 'code_ref');
    }

    public function produtos()
    {
        return $this->hasMany(CheckoutBuild::class);
    }

    public function depositos()
    {
        return $this->hasMany(Solicitacoes::class, 'user_id', 'user_id');
    }

    public function saques()
    {
        return $this->hasMany(SolicitacoesCashOut::class, 'user_id', 'user_id');
    }

    public function comissoes()
    {
        return $this->hasMany(Transactions::class, 'user_id', 'user_id');
    }
}
