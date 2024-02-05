import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { URL_BACKEND, URL_STORAGE } from '../config/enviroment.config';
import { NzMessageService } from 'ng-zorro-antd/message';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  user: any;
  token: string = '';
  URL_BACKEND: string = URL_BACKEND;
  URL_STORAGE: string = URL_STORAGE;
  constructor(
    private http: HttpClient,
    private msg: NzMessageService,
    private router: Router
  ) {
    this.loadLocalStorage();
  }

  // Iniciar Sesión
  login(email: string, password: string) {
    return new Promise((resolve, reject) => {
      this.http
        .post(this.URL_BACKEND + 'auth/login', { email, password })
        .subscribe(
          (res: any) => {
            resolve(this.saveLocalStorageResponse(res));
          },
          (error: HttpErrorResponse) => {
            reject(error);
          }
        );
    });
  }

  // Almacenar en localStorage la información
  saveLocalStorageResponse(resp: any) {
    if (resp.access_token && resp.user) {
      localStorage.setItem('token', resp.access_token);
      localStorage.setItem('user', JSON.stringify(resp.user));

      this.token = resp.access_token;
      this.user = resp.user;

      return true;
    }
    return false;
  }

  // Cerrar Sesión
  logout() {
    this.user = null;
    this.token = '';
    localStorage.removeItem('user');
    localStorage.removeItem('token');
    this.message('success', 'Sesión Cerrada');
    this.goRoute('login');
  }

  // Cargar información almacenada
  loadLocalStorage() {
    if (localStorage.getItem('token')) {
      this.token = localStorage.getItem('token') ?? '';
      this.user = JSON.parse(localStorage.getItem('user') ?? '');
    } else {
      this.token = '';
      this.user = null;
    }
  }

  message(type: string, message: string, duration: number = 3) {
    this.msg.create(type, message, {
      nzDuration: 1000 * duration,
    });
  }

  goRoute(route: string) {
    this.router.navigate([route]);
  }
}
