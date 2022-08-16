import React, { PureComponent } from 'react';
import CommonFunctions from '@jsroot/common/common-functions';
import DateTimeService from '@jsroot/common/dateTimeService';
export default class MobileStage extends PureComponent {
    render() {
        const { stages } = this.props;
        return (
            <div>
                Mobile Stage
                <select>
                    {
                        stages.map(group =>
                            <option key={group.key}>{CommonFunctions.getStageName(group.stage)}</option>)
                    }
                </select>
            </div>
        );
    }
}