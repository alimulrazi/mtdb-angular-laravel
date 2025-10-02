import {ChangeDetectionStrategy, Component} from '@angular/core';
import {MatDialogRef} from '@angular/material/dialog';
import {FormControl, FormGroup} from '@angular/forms';

@Component({
    selector: 'new-line-modal',
    templateUrl: './new-line-modal.component.html',
    styleUrls: ['./new-line-modal.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class NewLineModalComponent {
    public form = new FormGroup({
        key: new FormControl(),
        value: new FormControl(),
    });

    constructor(private dialogRef: MatDialogRef<NewLineModalComponent>) {}

    public confirm() {
        const formValue = this.form.value;
        if (formValue.key && formValue.value) {
            this.close({key: formValue.key, value: formValue.value});
        }
    }

    public close(line?: {key: string, value: string}) {
        this.dialogRef.close(line);
    }
}
