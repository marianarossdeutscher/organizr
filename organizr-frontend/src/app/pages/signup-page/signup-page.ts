import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { HttpClient, HttpClientModule, HttpErrorResponse } from '@angular/common/http';
import { Router, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-signup-page',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterLink
  ],
  templateUrl: './signup-page.html',
  styleUrls: ['./signup-page.scss']
})
export class SignupPage implements OnInit {

  signupForm!: FormGroup;
  submitted = false;
  passwordVisible = false;
  passwordFieldType = 'password';
  
  private apiUrl = '/api/auth/register';

  constructor(
    private formBuilder: FormBuilder,
    private http: HttpClient,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.signupForm = this.formBuilder.group({
      username: ['', [Validators.required, Validators.minLength(3)]],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(8)]]
    });
  }

  get f() {
    return this.signupForm.controls;
  }

  togglePasswordVisibility(): void {
    this.passwordVisible = !this.passwordVisible;
    this.passwordFieldType = this.passwordVisible ? 'text' : 'password';
  }

  onSubmit(): void {
    this.submitted = true;

    if (this.signupForm.invalid) {
      console.log('Formulário inválido');
      return;
    }

    const payload = this.signupForm.value;

    this.http.post(this.apiUrl, payload).subscribe({
      next: (response) => {
        console.log('Registo bem-sucedido!', response);
        this.router.navigate(['/login']); 
      },
      error: (err: HttpErrorResponse) => {
        console.error('Ocorreu um erro no registo:', err);
        const errorMessage = err.error?.message || 'Não foi possível criar a conta. Tente novamente.';
        alert(errorMessage);
      }
    });
  }
}