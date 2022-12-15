<!-- Style -->
<link rel="stylesheet" href="./css/Form.css" />


<?php
class RoomReportForm extends TableComponent
{
    public function __construct($props)
    {
        $this->fields =
            [
                'STT',
                'Phòng',
                'Số ngày thuê',
                'Tỷ lệ',
            ];
        $this->entries =
            [
                [
                    '1',
                    'A1.2',
                    '11',
                    '33.3%',
                ],
            ];
        $this->month = $props['month'];
    }

    public function render()
    {
        $fieldElements = $this->renderFields();
        $entryElements = $this->renderEntries();
        return <<< EOT
        <form class="report-form">
            <h2 class="form-title">Báo cáo mật độ sử dụng phòng</h2>
            <div class="form-month">Tháng <p>$this->month</p>
            </div>
            <div class="form-table">
                <table class="scrollable">
                    <thead>
                        $fieldElements
                    </thead>
                    <tbody>
                        $entryElements
                    </tbody>
                </table>
                <hr>
                <div class="form-summary">
                    <span>
                        Tổng số ngày thuê
                    </span>
                    <span class="total-using-days">
                        33 ngày
                    </span>
                </div>
            </div>
        </form>
        EOT;
    }
}
?>