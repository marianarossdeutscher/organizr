import { Component, Input, Output, EventEmitter, OnChanges, SimpleChanges, inject } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { Task } from '../../models/task-model.model';

@Component({
  selector: 'app-task-modal',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule
  ],
  providers: [DatePipe],
  templateUrl: './task-modal-component.html',
  styleUrls: ['./task-modal-component.scss']
})
export class TaskModalComponent implements OnChanges {
  @Input() task: Task | null = null;
  @Input() isOpen: boolean = false;
  @Input() initialStatus: string = 'To do';

  @Output() formSubmitted = new EventEmitter<Partial<Task>>();
  @Output() modalClosed = new EventEmitter<void>();

  private fb = inject(FormBuilder);
  private datePipe = inject(DatePipe);
  public taskForm: FormGroup;
  
  constructor() {
    this.taskForm = this.fb.group({
      title: ['', Validators.required],
      description: [''],
      endDate: [this.datePipe.transform(new Date(), 'yyyy-MM-dd')],
      priority: [1, Validators.required],
      status: ['To do', Validators.required]
    });
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (this.isOpen) {
      if (this.task) {
        this.taskForm.patchValue({
          ...this.task,
          endDate: this.datePipe.transform(this.task.endDate, 'yyyy-MM-dd')
        });
      } else {
        this.taskForm.reset({
          title: '',
          description: '',
          endDate: this.datePipe.transform(new Date(), 'yyyy-MM-dd'),
          priority: 1,
          status: this.initialStatus
        });
      }
    }
  }

  onSave(): void {
    if (this.taskForm.valid) {
      this.formSubmitted.emit(this.taskForm.value);
    }
  }

  onClose(): void {
    this.modalClosed.emit();
  }
}