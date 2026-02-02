@extends('layouts.app')

@section('title', 'Smart Alert - ' . $alert->title)

@section('content')
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
    <!-- Header -->
    <div style="background: linear-gradient(135deg, {{ $levelColor }} 0%, {{ $levelColor }}dd 100%); padding: 30px; border-radius: 10px; text-align: center; color: white; margin-bottom: 30px;">
        <div style="font-size: 24px; margin-bottom: 10px;">{{ $levelIcon }}</div>
        <h1 style="margin: 0; font-size: 28px; font-weight: bold;">{{ $alert->title }}</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Alerte générée automatiquement par StockApp</p>
    </div>

    <!-- Alert Content -->
    <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; margin-bottom: 20px;">
        <h2 style="color: #333; margin-top: 0; margin-bottom: 15px;">Détails de l'alerte</h2>
        
        <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid {{ $levelColor }}; margin-bottom: 15px;">
            <p style="margin: 0; font-size: 16px; color: #555; line-height: 1.6;">{{ $alert->message }}</p>
        </div>

        @if($alert->product)
        <div style="background: white; padding: 20px; border-radius: 8px;">
            <h3 style="color: #333; margin-top: 0; margin-bottom: 10px; font-size: 18px;">Produit concerné</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; font-weight: bold; color: #666;">Nom:</td>
                    <td style="padding: 8px; color: #333;">{{ $alert->product->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; color: #666;">Référence:</td>
                    <td style="padding: 8px; color: #333;">{{ $alert->product->reference ?? 'N/A' }}</td>
                </tr>
                @if($alert->product->category)
                <tr>
                    <td style="padding: 8px; font-weight: bold; color: #666;">Catégorie:</td>
                    <td style="padding: 8px; color: #333;">{{ $alert->product->category->name }}</td>
                </tr>
                @endif
            </table>
        </div>
        @endif

        @if($alert->data && !empty($alert->data))
        <div style="background: white; padding: 20px; border-radius: 8px;">
            <h3 style="color: #333; margin-top: 0; margin-bottom: 10px; font-size: 18px;">Données techniques</h3>
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($alert->data as $key => $value)
                <tr>
                    <td style="padding: 8px; font-weight: bold; color: #666;">{{ ucfirst(str_replace('_', ' ', $key)) }}:</td>
                    <td style="padding: 8px; color: #333;">{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>

    <!-- Actions -->
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="{{ url('/smart-alerts') }}" style="display: inline-block; background: {{ $levelColor }}; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 0 10px;">
            Voir les alertes
        </a>
        <a href="{{ url('/smart-alerts/analytics') }}" style="display: inline-block; background: #6366f1; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; margin: 0 10px;">
            Analytics
        </a>
    </div>

    <!-- Footer -->
    <div style="text-align: center; color: #666; font-size: 14px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
        <p>Cet email a été généré automatiquement par StockApp Smart Alerts</p>
        <p style="margin: 5px 0 0 0;">Date: {{ $alert->created_at->format('d/m/Y H:i') }}</p>
        <p style="margin: 5px 0 0 0;">ID Alert: {{ $alert->alert_id }}</p>
    </div>
</div>
@endsection
