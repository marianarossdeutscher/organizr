import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule, HttpErrorResponse } from '@angular/common/http';
import { Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-login-page',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterLink
  ],
  templateUrl: './login-page.html',
  styleUrls: ['./login-page.scss']
})
export class LoginPage implements OnInit {

  loginForm!: FormGroup;
  submitted = false;
  passwordVisible = false;
  passwordFieldType = 'password';
  
  private apiUrl = '/api/auth/login';

  constructor(
    private formBuilder: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.loginForm = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required]]
    });
  }

  get f() {
    return this.loginForm.controls;
  }

  togglePasswordVisibility(): void {
    this.passwordVisible = !this.passwordVisible;
    this.passwordFieldType = this.passwordVisible ? 'text' : 'password';
  }

  onSubmit(): void {
    this.submitted = true;

    if (this.loginForm.invalid) {
      console.log('Formulário inválido. Por favor, corrija os erros.');
      return;
    }

    const { email, password } = this.loginForm.value;

    this.http.post<{ token: string }>(this.apiUrl, { email, password }).subscribe({
      next: (response) => {
        console.log('Login bem-sucedido!');

        localStorage.setItem('authToken', response.token);

        this.router.navigate(['/home']); 

      },
      error: (err: HttpErrorResponse) => {
        console.error('Ocorreu um erro no login:', err);
        const errorMessage = err.error?.message || 'Não foi possível conectar ao servidor. Verifique as suas credenciais e tente novamente.';
        alert(errorMessage);
      }
    });
  }
}