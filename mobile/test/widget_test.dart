import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:app/widgets/ui.dart';

void main() {
  testWidgets('GoldButton affiche son libellé', (WidgetTester tester) async {
    await tester.pumpWidget(
      const MaterialApp(
        home: Scaffold(
          body: GoldButton(label: 'Se connecter'),
        ),
      ),
    );

    expect(find.text('Se connecter'), findsOneWidget);
  });
}
