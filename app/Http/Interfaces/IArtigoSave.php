<?php
namespace app\Http\Interfaces;

use App\Models\Artigo;
use Illuminate\Http\Request;

interface IArtigoSave
{
    function save(Request $request, Artigo $artigo);
}
