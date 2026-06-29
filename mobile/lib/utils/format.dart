import 'package:intl/intl.dart';

final _money = NumberFormat.decimalPattern('fr_FR');

String fcfa(num value) => '${_money.format(value.round())} FCFA';

String shortDate(String? iso) {
  if (iso == null || iso.isEmpty) return '—';
  try {
    final d = DateTime.parse(iso);
    return DateFormat('dd/MM/yyyy').format(d);
  } catch (_) {
    return iso;
  }
}
