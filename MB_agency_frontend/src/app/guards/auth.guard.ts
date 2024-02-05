import { Injectable } from '@angular/core';
import {
  ActivatedRouteSnapshot,
  CanActivate,
  RouterStateSnapshot,
  UrlTree,
} from '@angular/router';
import { Observable } from 'rxjs';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root',
})
export class AuthGuard implements CanActivate {
  constructor(private auth: AuthService) {}
  canActivate(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ):
    | Observable<boolean | UrlTree>
    | Promise<boolean | UrlTree>
    | boolean
    | UrlTree {
    if (!this.auth.user.token && !this.auth.user) {
      this.auth.message('error', 'Debe de Iniciar Sesión');
      this.auth.goRoute('login');
      return false;
    }

    let token = this.auth.token;
    let expire = JSON.parse(atob(token.split('.')[1])).exp;

    if (Math.floor(new Date().getTime() / 1000) >= expire) {
      this.auth.logout();
      this.auth.message('success', 'Sesión Cerrada');
      return false;
    }
    return true;
  }
}
