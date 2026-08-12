<?php
namespace App\Enums;
enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Mechanic = 'mechanic';
    case Workshop = 'workshop';
    case Store = 'store';
    case Transporte = 'transporte';
    // 'provider' eliminado — los proveedores son entidades propias (tabla providers, no users)
    // 'pro' eliminado — sin funcionalidad definida aún
}