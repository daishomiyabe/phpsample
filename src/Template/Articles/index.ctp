<!-- File: src/Template/Articles/index.ctp -->

<h1>�L���ꗗ</h1>
<table>
    <tr>
        <th>�^�C�g��</th>
        <th>�쐬����</th>
    </tr>

    <!-- �����ŁA$articles �N�G���[�I�u�W�F�N�g���J��Ԃ��āA�L���̏����o�͂��܂� -->

    <?php foreach ($articles as $article): ?>
    <tr>
        <td>
            <?= $this->Html->link($article->title, ['action' => 'view', $article->slug]) ?>
        </td>
        <td>
            <?= $article->created->format(DATE_RFC850) ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>