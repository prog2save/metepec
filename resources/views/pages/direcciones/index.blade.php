@extends('layouts.app')

@section('content')
<x-common.page-breadcrumb pageTitle="Direcciones Municipales" />
<div class="space-y-6">
    @if (session('success'))
    <div class="space-y-4">
        <x-ui.alert variant="success" title="{{ session('success') }}" message="" :showLink="false" linkHref="/"
            linkText="Learn more" />
    </div>
    @endif
    <x-common.component-card title="Lista de Direcciones Municipales">
        <x-tables.direcciones-table :direcciones="$direcciones" />
    </x-common.component-card>
</div>
@endsection